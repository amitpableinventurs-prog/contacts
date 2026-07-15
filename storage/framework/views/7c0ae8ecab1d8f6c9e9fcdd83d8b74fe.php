<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value']));

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

foreach (array_filter((['value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button type="button" role="tab"
        @click="active = '<?php echo e($value); ?>'"
        :aria-selected="active === '<?php echo e($value); ?>'"
        :class="active === '<?php echo e($value); ?>'
            ? 'bg-background text-foreground shadow'
            : 'text-muted-foreground hover:text-foreground'"
        <?php echo e($attributes->class([
            'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium',
            'transition-all focus-ring',
        ])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/tabs-trigger.blade.php ENDPATH**/ ?>