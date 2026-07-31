<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'default']));

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

foreach (array_filter((['variant' => 'default']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$variants = [
    'default' => 'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80',
    'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
    'destructive' => 'border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80',
    'success' => 'border-transparent bg-success text-success-foreground shadow hover:bg-success/80',
    'warning' => 'border-transparent bg-warning text-warning-foreground shadow hover:bg-warning/80',
    'outline' => 'text-foreground',
];
$classes = 'inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-semibold transition-colors focus-ring '
    . ($variants[$variant] ?? $variants['default']);
?>

<span <?php echo e($attributes->class($classes)); ?>>
    <?php echo e($slot); ?>

</span>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>