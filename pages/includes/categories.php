<div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>All departments</span>
                        </div>
                        
                        <ul>
   
    <li><a href="shop-grid.php">All</a></li>

    <?php
    $sql = "SELECT category_name FROM categories";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = htmlspecialchars($row['category_name']);
            echo '<li><a href="shop-grid.php?category=' . urlencode($category) . '">' . $category . '</a></li>';
        }
    } else {
        echo '<li>No categories found</li>';
    }
    ?>
</ul>

