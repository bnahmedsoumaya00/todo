//Aibility mchatba khater deprecated nst3mlou PureAibility wala Mongo  drachniaa yotba3 hasilou 
import {createMongoAbility } from "@casl/ability";

export function defineAbilitiesFor(user){
    const ability = createMongoAbility()
    if(!user){
        ability.rules = []
        return ability
    }
    if(user.role ==='admin'){
        ability.update([
            {action: 'manage', subject: 'all'},
            {action: 'access', subject: 'AdminDashboard'},
            {action: 'manage', subject: 'User'},
            {action: 'manage', subject:'Todo'}

        ])
        return ability
    }

    ability.update([
        {action: 'create', subject:'Todo'},
        {action: 'read', subject:'Todo', conditions: {user_id: user.id}},
        {action: 'update', subject:'Todo', conditions: {user_id: user.id}},
        {action: 'delete', subject:'Todo', conditions: {user_id: user.id}}
    ])

    return ability
}