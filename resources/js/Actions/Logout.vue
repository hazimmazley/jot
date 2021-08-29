<template>
  
</template>

<script>
export default {
    created() {
        axios.post('/logout', {})
        .finally(err => {
            window.location = '/login'
        })
    }
}
</script>

<style>

</style>