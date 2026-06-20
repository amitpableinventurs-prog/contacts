<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['align' => 'end', 'width' => 'w-56']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['align' => 'end', 'width' => 'w-56']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$alignment = match ($align) {
    'start' => 'left-0 origin-top-left',
    'center' => 'left-1/2 -translate-x-1/2 origin-top',
    default => 'right-0 origin-top-right',
};
?>

<div x-data="{ open: false }"
     @keydown.escape.window="open = false"
     class="relative inline-block text-left">
    <div @click="open = !open">
        <?php echo e($trigger); ?>

    </div>

    <div x-show="open"
         x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 <?php echo e($width); ?> <?php echo e($alignment); ?> rounded-md border bg-popover text-popover-foreground shadow-lg p-1 outline-none">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/dropdown-menu.blade.php ENDPATH**/ ?>