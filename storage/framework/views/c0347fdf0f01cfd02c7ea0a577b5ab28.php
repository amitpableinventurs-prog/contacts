<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['src' => null, 'name' => '', 'size' => 'default']));

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

foreach (array_filter((['src' => null, 'name' => '', 'size' => 'default']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$src = $src
    ? (str_starts_with($src, 'http') ? $src : asset('storage/'.$src))
    : null;

$sizes = [
    'sm' => 'h-7 w-7 text-xs',
    'default' => 'h-9 w-9 text-sm',
    'lg' => 'h-12 w-12 text-base',
    'xl' => 'h-20 w-20 text-2xl',
];
$initials = collect(preg_split('/\s+/', trim((string) $name)))
    ->filter()
    ->take(2)
    ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
    ->implode('') ?: '?';
?>

<span <?php echo e($attributes->class([
    'relative inline-flex shrink-0 overflow-hidden rounded-full bg-muted',
    'items-center justify-center font-medium text-muted-foreground',
    $sizes[$size] ?? $sizes['default'],
])); ?>>
    <?php if($src): ?>
        <img src="<?php echo e($src); ?>" alt="<?php echo e($name); ?>" class="aspect-square h-full w-full object-cover"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline';" />
        <span style="display:none;"><?php echo e($initials); ?></span>
    <?php else: ?>
        <span><?php echo e($initials); ?></span>
    <?php endif; ?>
</span>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/avatar.blade.php ENDPATH**/ ?>