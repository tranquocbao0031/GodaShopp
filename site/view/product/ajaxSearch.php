<ul class="list-unstyled">
    <?php foreach ($products as $product): ?>
    <li>
        <a class="product-name" href="?c=product&a=detail&id=<?=$product->getId()?>" title="<?=$product->getName()?>">
            <img style="width:50px" src="../upload/<?=$product->getFeaturedImage()?>" alt="">
            <?=$product->getName()?>
        </a>
    </li>
    <?php endforeach ?>
</ul>