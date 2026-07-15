<textarea
    <?php echo e($attributes->class([
        'flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm',
        'placeholder:text-muted-foreground',
        'focus-ring',
        'disabled:cursor-not-allowed disabled:opacity-50',
    ])); ?>><?php echo e($slot); ?></textarea>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/textarea.blade.php ENDPATH**/ ?>