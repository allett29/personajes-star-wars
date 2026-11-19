/**
 * Clases Tailwind reutilizables (modo claro + oscuro).
 * Centralizar aquí evita repetir strings largos en cada componente.
 */

export const pageWrapper =
    'min-h-screen bg-gray-50 py-8 transition-colors duration-200 dark:bg-gray-900';

export const pageContainer = 'mx-auto max-w-5xl px-4';

export const card =
    'rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition dark:border-gray-700 dark:bg-gray-800';

export const tituloPagina = 'text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100';

export const labelCampo = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

export const inputCampo =
    'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-indigo-400';

export const selectCampo = inputCampo;

export const btnPrimario =
    'inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-50 dark:bg-indigo-500 dark:hover:bg-indigo-600';

export const btnSecundario =
    'inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400/30 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';

export const btnPeligro =
    'inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/40 disabled:opacity-50';

export const btnAcento =
    'inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500/40 disabled:opacity-50';

export const tablaWrap =
    'overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm transition dark:border-gray-700 dark:bg-gray-800';

export const tablaHead = 'bg-gray-100 text-gray-700 dark:bg-gray-700/60 dark:text-gray-200';

export const tablaFila =
    'border-t border-gray-100 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/40';

export const enlaceAccion = 'font-medium text-indigo-600 hover:underline dark:text-indigo-400';