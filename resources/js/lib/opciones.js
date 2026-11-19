/**
 * Opciones de género: `valor` es lo que se guarda en la base de datos
 * y `texto` es lo que ve la persona usuaria.
 */
export const GENEROS = [
    { valor: 'masculino', texto: 'Masculino' },
    { valor: 'femenino', texto: 'Femenino' },
    { valor: 'n/a', texto: 'No aplica' },
];

export function textoGenero(valor) {
    return GENEROS.find((genero) => genero.valor === valor)?.texto ?? valor;
}
