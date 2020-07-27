<template>
    <span>
        <span v-show="state === true">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="sr-only">Loading...</span>
        </span>
        <span v-show="state === false">
            <slot></slot>
        </span>
    </span>
</template>

<script>
    export default {
        name: "Spinner",
        props: {
            state: Boolean,
            default: false,
        },
    }
</script>
