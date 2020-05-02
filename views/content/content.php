<script>
    $(document).ready(function (e) {
        $('#count-publish').text('<?php echo $publish_count; ?>');
        $('#count-moder').text('<?php echo $moder_count; ?>');
        $('#count-reject').text('<?php echo $reject_count; ?>');
    })
</script>