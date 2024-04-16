// Generated by https://quicktype.io

export interface peliculas {
    id_pelicula?:      number;
    titulo?:           string;
    anno_estreno?:     number;
    duracion_minutos?: number;
    genero?:           string | number;
    imagen?: string;
}

export interface usuario {
    id_usuario?: number;
    nombre?:    string;
    email?:     string;
    password?: string;
}

export interface favoritas {
    id_favorita: number;
    id_pelicula: number;
    id_usuario: number;
    calificacion: number;
    titulo: string;
    genero: string;
    anno_estreno: string;
    duracion_minutos: string;
    imagen: string;
}