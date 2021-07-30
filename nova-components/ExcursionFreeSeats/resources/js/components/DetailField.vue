<template>
    <panel-item :field="field">
        <template slot="value">
            <p  class="">
                {{ field.freeSeats }}
            </p>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
