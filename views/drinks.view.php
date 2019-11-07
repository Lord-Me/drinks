<!DOCTYPE html>
<html lang="en">
<?php
require('partials/head.partials.php');
?>
<!--<script>
    //https://stackoverflow.com/questions/36217365/php-ajax-checkbox-filter-using-data-tag-attribute TODO figure this out with fernandez
$(document).ready(function(){
    $('.genre').on('change', function(){
        var genreList = [];

        $('#filterContainer :input:checked').each(function(){
            var genre = $(this).val();
            genreList.push(genre);
        });

        if(genreList.length == 0)
        $('.blItem').fadeIn();
        else {
            $('.blItem').each(function(){
                var item = $(this).attr('data-tag');
                var items = item.split(',');

                $(this).hide();
                for (var i=0;i<items.length;i++) {
                    if(jQuery.inArray(items[i],genreList) > -1)
                    $(this).fadeIn('slow');
                }
            });
        }
    });
});
</script>-->


</head>
<body>
<?php
require('partials/navigation.partials.php');
?>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<br><br><br>
<div id="filterContainer">
    <div class="filter">
        <input id="check1" type="checkbox" name="check" value="Novel" class="genre">
        <label for="check1">Novel</label>
    </div>

    <div class="filter">
        <input id="check2" type="checkbox" name="check" value="Fiction" class="genre">
        <label for="check2">Fiction</label>
    </div>

    <div class="filter">
        <input id="check3" type="checkbox" name="check" value="Non-Fiction" class="genre">
        <label for="check3">Non-Fiction</label>
    </div>
</div>

<div class="booksList in fade">
    <div class="blItem" data-tag="X,Fiction">Item X and Fiction</div>
    <div class="blItem" data-tag="Non-Fiction,Y">Item Y and Non-Fiction</div>
    <div class="blItem" data-tag="Non-Fiction,Fiction">Item Fiction and Non-Fiction</div>
</div>-->

<?php
echo $view;
?>

<?php
require('partials/footer.partials.php');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/scrollify-master/jquery.scrollify.js"></script>
<script>
    $(document).ready(function() {
        $.scrollify({
            section : ".scroll",
        });
    });
</script>
</body>
</html>
