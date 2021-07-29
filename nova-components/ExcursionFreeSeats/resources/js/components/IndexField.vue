<template>
    <span>{{ field.freeSeats }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
