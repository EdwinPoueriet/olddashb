<template>
  <div>
    <input type="checkbox" v-on:click="confirmtest" value="" />
  </div>
</template>

<script>
export default {
  methods: {
    confirmtest() {
      confirm("Press");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>