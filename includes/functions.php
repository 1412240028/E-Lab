<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function elab_start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function elab_redirect(string $path, ?string $type = null, ?string $message = null, array $extra = []): void
{
    $target = $path;
    $query = [];

    if ($type !== null && $message !== null && $message !== '') {
        $query[$type] = $message;
    }

    foreach ($extra as $key => $value) {
        if ($value !== null && $value !== '') {
            $query[$key] = (string) $value;
        }
    }

    if (!empty($query)) {
        $target .= (strpos($path, '?') !== false ? '&' : '?') . http_build_query($query);
    }

    header("Location: $target");
    exit;
}

function elab_require_role(array $allowedRoles): void
{
    elab_start_session();

    if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
        elab_redirect('../login.php', 'error', 'Silakan login terlebih dahulu');
    }

    if (!in_array($_SESSION['role'], $allowedRoles, true)) {
        elab_redirect('../login.php', 'error', 'Anda tidak memiliki akses ke halaman ini');
    }
}

function elab_sanitize_text($value, string $default = ''): string
{
    if ($value === null) {
        return $default;
    }

    $text = trim((string) $value);
    $text = stripslashes($text);

    return $text !== '' ? $text : $default;
}

function elab_has_schedule_conflict(mysqli $conn, int $idLab, string $tanggalPinjam, string $jamMulai, string $jamSelesai): bool
{
    $stmt = mysqli_prepare($conn, "
        SELECT id_peminjaman
        FROM peminjaman
        WHERE id_lab = ?
        AND tanggal_pinjam = ?
        AND status = 'disetujui'
        AND jam_mulai < ?
        AND jam_selesai > ?
        LIMIT 1
    ");

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'isss', $idLab, $tanggalPinjam, $jamSelesai, $jamMulai);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

function elab_log_activity(string $action, array $context = []): void
{
    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/activity.log';
    $entry = sprintf(
        '[%s] %s %s',
        date('Y-m-d H:i:s'),
        $action,
        json_encode($context, JSON_UNESCAPED_UNICODE)
    );

    file_put_contents($logFile, $entry . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function elab_render_alerts($error = null, $success = null): string
{
    $messages = [];

    if ($error !== null && $error !== '') {
        $messages[] = ['type' => 'danger', 'message' => $error];
    } elseif (isset($_GET['error']) && $_GET['error'] !== '') {
        $messages[] = ['type' => 'danger', 'message' => $_GET['error']];
    }

    if ($success !== null && $success !== '') {
        $messages[] = ['type' => 'success', 'message' => $success];
    } elseif (isset($_GET['success']) && $_GET['success'] !== '') {
        $messages[] = ['type' => 'success', 'message' => $_GET['success']];
    }

    if (empty($messages)) {
        return '';
    }

    $html = '';
    foreach ($messages as $message) {
        $html .= sprintf(
            '<div class="alert alert-%s mb-3">%s</div>',
            htmlspecialchars($message['type']),
            htmlspecialchars($message['message'])
        );
    }

    return $html;
}
