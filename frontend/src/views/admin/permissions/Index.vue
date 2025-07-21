<template>
  <div class="space-y-5">
    <PermissionsTable/>
  </div>
</template>
<script>
import PermissionsTable from "./Table.vue";
export default {
  components: {
    PermissionsTable,
  },
};
</script>
<style lang="scss">
.vgt-inner-wrap {
  @apply shadow-none;
}
</style>
