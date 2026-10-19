// Add category with AJAX
document.getElementById('addCategoryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    let categoryName = document.getElementById('categoryName').value;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../../ajax/admin_category.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            let response = JSON.parse(this.responseText);
            if (response.success) {
                alert('Category added successfully');
                location.reload();
            } else {
                alert('Failed to add category');
            }
        }
    };
    
    xhr.send(`action=add&name=${categoryName}`);
});