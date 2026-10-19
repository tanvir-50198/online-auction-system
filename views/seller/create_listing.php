<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

$categories = getAllCategories($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $starting_price = (float)($_POST['starting_price'] ?? 0);
    $end_datetime = $_POST['end_datetime'] ?? '';
    $image = '';

    // Validate
    $errors = [];
    if (empty($title)) $errors[] = 'Title required';
    if ($category_id <= 0) $errors[] = 'Please select a category';
    if ($starting_price <= 0) $errors[] = 'Starting price must be > 0';
    if (empty($end_datetime) || strtotime($end_datetime) <= time()) $errors[] = 'End date must be in future';

    // Image is optional; if one was chosen it must upload successfully
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $img = uploadFile($_FILES['image'], '../../uploads/listings/');
        if ($img) {
            $image = $img;
        } else {
            $errors[] = $GLOBALS['upload_error'] ?: 'Image upload failed';
        }
    }

    if (empty($errors)) {
        if (createListing($conn, $_SESSION['user_id'], $category_id, $title, $description, $starting_price, $end_datetime, $image)) {
            header("Location: manage_listings.php?created=1");
            exit();
        } else {
            $message = 'Failed to create listing';
        }
    } else {
        $message = implode(', ', $errors);
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>Create new auction</h1>
        <p>Add clear details and a good photo to attract more bidders.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-error" data-persist><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="form-card">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" maxlength="200" placeholder="e.g. Vintage film camera" value="<?php echo e($_POST['title'] ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5" placeholder="Condition, size, history, anything a buyer should know..."><?php echo e($_POST['description'] ?? ''); ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="starting_price">Starting price</label>
            <div class="input-group">
                <span class="input-prefix">$</span>
                <input type="number" id="starting_price" step="0.01" min="0.01" name="starting_price" placeholder="0.00" value="<?php echo e($_POST['starting_price'] ?? ''); ?>" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="end_datetime">Auction ends</label>
        <input type="datetime-local" id="end_datetime" name="end_datetime" value="<?php echo e($_POST['end_datetime'] ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="imageInput">Product image <small>(JPG, PNG, GIF or WEBP, max 5 MB)</small></label>
        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/gif,image/webp">
        <img id="imagePreview" class="image-preview" alt="Image preview" style="display:none;">
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">Create auction</button>
        <a href="manage_listings.php" class="btn btn-lg">Cancel</a>
    </div>
</form>

<script>
document.getElementById('imageInput').addEventListener('change', function () {
    var preview = document.getElementById('imagePreview');
    var file = this.files[0];
    if (!file) { preview.style.display = 'none'; return; }
    if (file.size > 5 * 1024 * 1024) {
        alert('Image must be smaller than 5 MB');
        this.value = '';
        preview.style.display = 'none';
        return;
    }
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
});
</script>

<?php include '../partials/footer.php'; ?>
