import { computed } from 'vue'

export function useGroupedPermissions(permissionsRef) {
  const groupPermissions = (permissions) => {
    const grouped = {}
    permissions.forEach(({ name }) => {
      const [module, action] = name.split('.')
      if (!grouped[module]) grouped[module] = []
      grouped[module].push(action)
    })
    return grouped
  }

  const grouped = computed(() =>
    permissionsRef.value?.length ? groupPermissions(permissionsRef.value) : {}
  )

  return {
    grouped,
  }
}
