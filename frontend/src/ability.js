import { createMongoAbility } from '@casl/ability'
import { createAbilityPlugin } from '@casl/vue'

export const ability = createMongoAbility()
export const abilityPlugin = createAbilityPlugin(ability)