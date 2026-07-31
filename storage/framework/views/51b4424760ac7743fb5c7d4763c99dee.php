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
$origin = match ($align) {
    'start' => 'origin-top-left',
    'center' => 'origin-top -translate-x-1/2',
    default => 'origin-top-right',
};
?>


<div x-data="{
        open: false,
        menuStyle: '',
        toggle() {
            if (this.open) { this.open = false; return; }
            const r = this.$el.getBoundingClientRect();
            let s = (window.innerHeight - r.bottom < 260 && r.top > window.innerHeight - r.bottom)
                ? 'bottom:' + Math.round(window.innerHeight - r.top + 6) + 'px;'
                : 'top:' + Math.round(r.bottom + 6) + 'px;';
            <?php if($align === 'start'): ?>
                s += 'left:' + Math.max(8, Math.round(r.left)) + 'px;';
            <?php elseif($align === 'center'): ?>
                s += 'left:' + Math.round(r.left + r.width / 2) + 'px;';
            <?php else: ?>
                s += 'right:' + Math.max(8, Math.round(window.innerWidth - r.right)) + 'px;';
            <?php endif; ?>
            this.menuStyle = s;
            this.open = true;
        },
     }"
     @keydown.escape.window="open = false"
     @scroll.window.passive="open = false"
     @resize.window.passive="open = false"
     class="relative inline-block text-left">
    <div @click="toggle()">
        <?php echo e($trigger); ?>

    </div>

    <div x-show="open"
         x-cloak
         :style="menuStyle"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-50 <?php echo e($width); ?> <?php echo e($origin); ?> max-h-[70vh] overflow-y-auto rounded-md border bg-popover text-popover-foreground shadow-lg p-1 outline-none">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/components/ui/dropdown-menu.blade.php ENDPATH**/ ?>