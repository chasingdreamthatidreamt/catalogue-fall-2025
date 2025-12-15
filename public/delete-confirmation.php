<?php
$id = $_GET['id'];
?>
<?php include('includes/header.php'); ?>
<h2>Confirm Delete</h2>
<p>Are you sure you want to delete this item?</p>
<form method="post" action="delete.php?id=<?php echo $id; ?>">
    <button type="submit">Yes, Delete</button>
</form>
<a href="view.php?id=<?php echo $id; ?>">Cancel</a>
<?php include('includes/footer.php'); ?>