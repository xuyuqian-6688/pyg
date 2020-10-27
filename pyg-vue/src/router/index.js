import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Login from '@/components/Login'
import Index from '@/components/Indexs'
import Indexs from '@/components/Ind'

import Platform from '@/components/Platform'
import manager_auth from '@/components/Platforms/manager_auth'
import ManagerAdmin from '@/components/Platforms/ManagerAdmin'
import ManagerRole from '@/components/Platforms/ManagerRole'
import ManagerLog from '@/components/Platforms/ManagerLog'


import Shop from '@/components/Shop'
import GoodsCategory from '@/components/Shops/GoodsCategory'
import GoodsBrand from '@/components/Shops/GoodsBrand'
import GoodsType from '@/components/Shops/GoodsType'
import GoodsList from '@/components/Shops/GoodsList'
import manager_order from '@/components/Shops/manager_order'
import complain_order from '@/components/Shops/complain_order'
import refund_order from '@/components/Shops/refund_order'


import Operator from '@/components/Operator'




Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', name: 'HelloWorld', component: HelloWorld },
    // 登陆
    { path: '/login', name: 'login', component: Login },
    {
      path: '/index', name: 'index', component: Index, children: [
        { path: '/indexs', component: Indexs },
        {
          path: '/platform', component: Platform, children: [
            
            { path: '/manager_auth', component: manager_auth },
            { path: '/manager_admin', component: ManagerAdmin },
            { path: '/manager_role', component: ManagerRole },
            { path: '/manager_log', component: ManagerLog },
          ]
        },

        {
          path: '/shop', component: Shop, children: [
            { path: '/goods_category', component: GoodsCategory },
            { path: '/goods_brand', component: GoodsBrand },
            { path: '/goods_type', component: GoodsType },
            { path: '/goods_list', component: GoodsList },

            { path: '/manager_order', component: manager_order },
            { path: '/complain_order', component: complain_order },
            { path: '/refund_order', component: refund_order },
            
            

          ]
        },
        { path: '/operator', component: Operator },
      ]
    }
  ]
})
