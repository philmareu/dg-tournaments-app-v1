<template>
    <div :id="id" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title"><slot name="title"></slot></h2>
            </div>

            <div class="uk-modal-body" uk-overflow-auto>
                <slot></slot>
            </div>

            <div class="uk-modal-footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        methods: {
            close() {
                this.$emit('closing');
            }
        }
    }
</script>