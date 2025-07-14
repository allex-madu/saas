<template>
  <div class="space-y-5">
    <UsersTable/>
  </div>
</template>
<script>
import UsersTable from "./UsersTable.vue";
export default {
  components: {
    UsersTable,
    
  },
};
</script>
<style lang="scss">
.vgt-inner-wrap {
  @apply shadow-none;
}
</style>
