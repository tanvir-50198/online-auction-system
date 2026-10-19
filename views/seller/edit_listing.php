<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

$listing_id = (int)$_GET['id'];
$listing = getListingById($conn, $listing_id);
if (!$listing || $listing['seller_id'] != $_SESSION['user_id'] || hasBids($conn, $listing_id)) {
    header("Location: manage_listings.php");
    exit();
}

$categories = getAllCategories($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $end_datetime = $_POST['end_datetime'];

    if (strtotime($end_datetime) <= time()) {
        $message = 'End date must be in future';
    } else {
        // Optional: replace the image
        $newImage = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newImage = uploadFile($_FILES['image'], '../../uploads/listings/');
            if (!$newImage) {
                $message = $GLOBALS['upload_error'] ?: 'Image upload failed';
            }
        }

        if ($message === '') {
            if (updateListing($conn, $listing_id, $title, $description, $category_id, $end_datetime)) {
                if ($newImage) {
                    updateListingImage($conn, $listing_id, $newImage);
                }
                header("Location: manage_listings.php?updated=1");
                exit();
            } else {
                $message = 'Update failed';
            }
        }
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>Edit listing</h1>
        <p>Listings can only be edited while they have no bids.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-error" data-persist><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="form-card">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo e($listing['title']); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5" required><?php echo e($listing['description']); ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $listing['category_id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="end_datetime">Auction ends</label>
            <input type="datetime-local" id="end_datetime" name="end_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($listing['end_datetime'])); ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="imageInput">Product image <small>(leave empty to keep the current image)</small></label>
        <img id="imagePreview" class="image-preview" src="<?php echo listingImageUrl($listing['image']); ?>" alt="Current image">
        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/gif,image/webp">
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">Save changes</button>
        <a href="manage_listings.php" class="btn btn-lg">Cancel</a>
    </div>
</form>

<script>
document.getElementById('imageInput').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { alert('Image must be smaller than 5 MB'); this.value = ''; return; }
    document.getElementById('imagePreview').src = URL.createObjectURL(file);
});
</script>

<?php include '../partials/footer.php'; ?>
