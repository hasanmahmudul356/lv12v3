import { createRouter, createWebHistory } from 'vue-router';
import { backend } from './backend';

const router = createRouter({
  history: createWebHistory('/app'),  // <-- set base to /app
  routes: backend,
});

export default router;
