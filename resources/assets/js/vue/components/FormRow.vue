<template>
    <div class="uk-margin">
        <label :for="id" class="uk-form-label"><slot name="label"></slot></label>
        <div class="uk-form-controls">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id']
    }
</script>