# Skill: Laravel + Inertia + Vue (resumen local)

Origen: [haythamasalama/skills — laravel-inertia-vue](https://github.com/haythamasalama/skills/tree/main/skills/laravel-inertia-vue)

Principios aplicados en este proyecto (sin shadcn/TypeScript, para mantener la prueba simple):

1. Layout común (`AppLayout`) + cabecera de página (`PageHeader`).
2. Tokens de color en CSS (`--surface`, `--text`) y modo oscuro con clase `dark` en `<html>`.
3. Acciones destructivas con `ConfirmDialog`, no `window.confirm`.
4. Modales con `ModalBase` y transiciones Tailwind.
5. Filtros y orden en la URL; el servidor pagina y filtra.
6. Toasts/éxito vía flash de Laravel + `FlashMessage`.
7. Componentes de casa reutilizables; sin colores hex sueltos en cada vista.

Para el detalle completo del skill original, ver el enlace de arriba.