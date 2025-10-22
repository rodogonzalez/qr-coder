import { createRouter, createWebHistory } from 'vue-router';
import FrmPost from './components/dashboard/FrmMessage.vue';
import MsgRendered from './components/dashboard/MsgRendered.vue';



export const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: FrmPost },
    { path: '/:telefono/:mensaje', component: MsgRendered },
    

]}

) ;
