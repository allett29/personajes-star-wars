/**
 * Clases Tailwind compartidas por toda la interfaz.
 * Cambiar un estilo aquí lo actualiza en todos los componentes.
 */

const botonBase =
    'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition ' +
    'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-espacio-950 ' +
    'disabled:cursor-not-allowed disabled:opacity-60';

export const btnPrimario = `${botonBase} bg-blue-600 text-white hover:bg-blue-700 focus-visible:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400`;

export const btnSecundario = `${botonBase} border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus-visible:ring-slate-400 dark:border-espacio-700 dark:bg-espacio-900 dark:text-slate-200 dark:hover:bg-espacio-800`;

export const btnPeligro = `${botonBase} bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-500`;

export const btnTexto =
    'rounded-md px-2 py-1 text-sm font-medium text-blue-700 hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-blue-300 dark:hover:bg-espacio-800';

export const btnTextoPeligro =
    'rounded-md px-2 py-1 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-red-400 dark:hover:bg-red-500/10';

export const inputBase =
    'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 ' +
    'focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 ' +
    'dark:border-espacio-700 dark:bg-espacio-950 dark:text-slate-100 dark:placeholder:text-slate-500';

export const tarjeta = 'rounded-xl border border-slate-200 bg-white dark:border-espacio-800 dark:bg-espacio-900';
