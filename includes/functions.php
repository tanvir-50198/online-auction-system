<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone);
}

/**
 * Upload an image. Returns saved file name on success, false on failure.
 * The reason for a failure is stored in $GLOBALS['upload_error'].
 *
 * FIX: relative target dirs (e.g. '../../uploads/listings/') used to be resolved
 * from the wrong folder (includes/), so files were saved outside the project.
 * Now every relative path is resolved from the project root.
 */
function uploadFile($file, $targetDir) {
    $GLOBALS['upload_error'] = '';

    if (!isset($file) || !isset($file['name']) || !isset($file['error'])) {
        $GLOBALS['upload_error'] = 'No file received';
        return false;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'Image is larger than the server limit (upload_max_filesize in php.ini)',
            UPLOAD_ERR_FORM_SIZE  => 'Image is too large',
            UPLOAD_ERR_PARTIAL    => 'Image was only partially uploaded, try again',
            UPLOAD_ERR_NO_FILE    => 'No image selected',
            UPLOAD_ERR_NO_TMP_DIR => 'Server temp folder is missing',
            UPLOAD_ERR_CANT_WRITE => 'Server cannot write the file to disk',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload',
        ];
        $GLOBALS['upload_error'] = $errors[$file['error']] ?? 'Unknown upload error';
        return false;
    }

    // Validate extension, size and real image content
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes, true)) {
        $GLOBALS['upload_error'] = 'Only JPG, PNG, GIF or WEBP images are allowed';
        return false;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        $GLOBALS['upload_error'] = 'Image must be smaller than 5 MB';
        return false;
    }
    if (@getimagesize($file['tmp_name']) === false) {
        $GLOBALS['upload_error'] = 'File is not a valid image';
        return false;
    }

    // Resolve target directory from the project root (no regex, no backslash tricks)
    $projectRoot = str_replace(chr(92), '/', dirname(__DIR__));
    $targetDir   = str_replace(chr(92), '/', $targetDir);
    $isAbsolute  = (substr($targetDir, 0, 1) === '/') || (substr($targetDir, 1, 1) === ':');

    if ($isAbsolute) {
        $absTarget = $targetDir;
    } else {
        $relative  = ltrim(str_replace(['../', './'], '', $targetDir), '/');
        $absTarget = $projectRoot . '/' . $relative;
    }
    $absTarget = rtrim($absTarget, '/') . '/';

    if (!is_dir($absTarget) && !mkdir($absTarget, 0777, true)) {
        $GLOBALS['upload_error'] = 'Upload folder could not be created';
        return false;
    }
    if (!is_writable($absTarget)) {
        $GLOBALS['upload_error'] = 'Upload folder is not writable';
        return false;
    }

    // Safe unique file name
    $fileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], $absTarget . $fileName)) {
        return $fileName;
    }
    $GLOBALS['upload_error'] = 'Could not move uploaded file';
    return false;
}

/** URL for a listing image, with a placeholder when none exists. */
function listingImageUrl($image) {
    $image = basename((string)$image);
    $file  = dirname(__DIR__) . '/uploads/listings/' . $image;
    if ($image !== '' && is_file($file)) {
        return '/auction-system/uploads/listings/' . rawurlencode($image);
    }
    return '/auction-system/assets/images/placeholder.svg';
}

/** URL for a profile picture, with an avatar placeholder when none exists. */
function profileImageUrl($image) {
    $image = basename((string)$image);
    $file  = dirname(__DIR__) . '/uploads/profiles/' . $image;
    if ($image !== '' && is_file($file)) {
        return '/auction-system/uploads/profiles/' . rawurlencode($image);
    }
    return '/auction-system/assets/images/avatar.svg';
}


/* ==========================================================================
   View helpers (UI)
   ========================================================================== */

/** Escape for HTML output. Already-encoded entities (from sanitize()) are kept as is. */
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', false);
}

/** Inline SVG icon. */
function icon($name, $size = 20) {
    $paths = [
        'gavel'    => '<path d="M14 13l-8.5 8.5a2.1 2.1 0 0 1-3-3L11 10"/><path d="M16 16l6-6"/><path d="M8 8l6-6"/><path d="M9 7l8 8"/><path d="M21 11l-8-8"/>',
        'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'package'  => '<path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
        'layers'   => '<path d="M12 2l10 5-10 5L2 7z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
        'trending' => '<path d="M22 7l-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
        'heart'    => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21.2l7.8-7.7 1-1.1a5.5 5.5 0 0 0 0-7.8z"/>',
        'dollar'   => '<path d="M12 2v20"/><path d="M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'image'    => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/>',
        'check'    => '<path d="M20 6L9 17l-5-5"/>',
        'lock'     => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'bolt'     => '<path d="M13 2L4 14h7l-1 8 9-12h-7z"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    ];
    $p = $paths[$name] ?? '';
    return '<svg class="icon" width="' . (int)$size . '" height="' . (int)$size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $p . '</svg>';
}

/** Status badge: <span class="badge status-xxx">Label</span> */
function statusBadge($status, $label = null) {
    $key = strtolower(preg_replace('/[^a-z]/i', '', (string)$status));
    $label = $label ?? ucfirst((string)$status);
    return '<span class="badge status-' . e($key) . '">' . e($label) . '</span>';
}

/** Auction listing card used by the buyer pages. $footer = custom footer HTML (optional). */
function renderListingCard($l, $footer = null) {
    $id    = (int)$l['id'];
    $img   = listingImageUrl($l['image'] ?? '');
    $title = e($l['title']);
    $cat   = e($l['category_name'] ?? '');
    $price = number_format((float)$l['current_bid'], 2);
    $end   = e($l['end_datetime']);
    $link  = 'details.php?id=' . $id;
    $bidsBlock = isset($l['bid_count'])
        ? '<div class="right"><span class="label">Bids</span><span class="num">' . (int)$l['bid_count'] . '</span></div>'
        : '';
    if ($footer === null) {
        $footer = '<a href="' . $link . '" class="btn btn-primary btn-sm">View &amp; bid</a>';
    }
    return '<article class="listing-card">'
        . '<a class="listing-media" href="' . $link . '">'
        .   '<img src="' . $img . '" alt="' . $title . '" loading="lazy">'
        .   ($cat !== '' ? '<span class="chip chip-cat">' . $cat . '</span>' : '')
        .   '<span class="chip chip-time" data-end="' . $end . '">' . icon('clock', 14) . ' <span class="t">--</span></span>'
        . '</a>'
        . '<div class="listing-body">'
        .   '<h3 class="listing-title"><a href="' . $link . '">' . $title . '</a></h3>'
        .   '<div class="listing-meta"><div><span class="label">Current bid</span><span class="price">$' . $price . '</span></div>' . $bidsBlock . '</div>'
        . '</div>'
        . '<div class="listing-foot">' . $footer . '</div>'
        . '</article>';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">' . 
             $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}
?>