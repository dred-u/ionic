import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { UserDataService } from 'src/app/services/user-data.service';
import { peliculas } from '../../interfaces/index';
import { Router } from '@angular/router';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss']
})
export class Tab1Page implements OnInit {
  public resp: peliculas[] = [];
  id: number;
  editId: number = 0;

  constructor(private apiService: ApiService, private router: Router, private userDataService: UserDataService) {
    this.id = parseInt(this.userDataService.getId())
  }
  
  ngOnInit() {
    this.obtenerDatos();
  }

  obtenerDatos() {
    this.apiService.getTopHeadlines()
      .subscribe(resp => {
        console.log(resp); // Imprime el objeto peliculas o arreglo peliculas en la consola
        if (Array.isArray(resp)) {
          this.resp = resp; // Si es un arreglo, asigna directamente
        } else {
          this.resp = [resp]; // Si es un objeto, envuélvelo en un arreglo antes de asignar
        }
      });
  }

  formPost = new FormGroup({
    titulo: new FormControl<string>('', [Validators.required]),
    genero: new FormControl<any>(null, [Validators.required]),
    anno_estreno: new FormControl<any>(null, [Validators.required]),
    duracion_minutos: new FormControl<any>(null, [Validators.required]),
    imagen: new FormControl<string>('', [Validators.required]),
  });

  isModalOpen = false;
  editModalOpen = false;

  setOpen(isOpen: boolean) {
    this.isModalOpen = isOpen;
  }


  generoMapping: { [key: string]: number } = {
    "Acción": 1,
    "Aventura": 2,
    "Drama": 3,
    "Fantasía": 4,
    "Musical": 5,
    "Comedia": 6
  };

  async crearDato() {
    if (this.formPost.valid) {
      const titulo = this.formPost.value.titulo as string;
      const generoNombre = this.formPost.value.genero as string;
      const genero = this.generoMapping[generoNombre]; // Obtener el valor numérico del género seleccionado
      const anno_estreno = this.formPost.value.anno_estreno as number;
      const duracion_minutos = this.formPost.value.duracion_minutos as number;
      const imagen = this.formPost.value.imagen as string;

      const Movie: peliculas = {
        titulo,
        genero,
        anno_estreno,
        duracion_minutos,
        imagen
      };

      console.log(Movie);

      this.apiService.postDatos(Movie).subscribe(response => {
        console.log(response);
        // Recargar datos después de crear uno nuevo
        this.obtenerDatos();
      });
    }
  }

  eliminarDato(id: number) {
    const objetoEliminar = {
      id_pelicula: id
    };
    this.apiService.eliminarDato(objetoEliminar).subscribe(
      (response: any) => {
          console.log(response);
          this.resp = this.resp.filter(item => item.id_pelicula !== id);
      },
      error => {
        console.error("Error al eliminar el registro:", error);
      }
    );
  }

  datoFavorito(id: number) {
    const objeto = {
      id_pelicula: id,
      id_usuario: this.id
    };
    this.apiService.postFavorita(objeto).subscribe(
      (response: any) => {
          console.log(response);
          this.router.navigate(['/tabs/tab2']);
      },
      error => {
        console.error("Error al eliminar el registro:", error);
      }
    );
  }

  setEditOpen(isOpen: boolean, id: number) {
    this.editModalOpen = isOpen;
    if (!isOpen) {
      this.formPost.reset();
    } else if (id !== 0) {
      this.editId = id;
      this.cargarDatosPelicula(id);
    } 
  }

  cargarDatosPelicula(id: number) {
    // Buscar la película en el array resp por su ID
    const pelicula = this.resp.find(item => item.id_pelicula === id);
    if (pelicula) {
      // Asignar los valores obtenidos al formulario de edición
      this.formPost.patchValue({
        titulo: pelicula.titulo,
        genero: pelicula.genero,
        anno_estreno: pelicula.anno_estreno,
        duracion_minutos: pelicula.duracion_minutos,
        imagen: pelicula.imagen
      });
    }
  }

  async editarDato() {
    if (this.editModalOpen) { 
      const id_pelicula = this.editId;
      if (id_pelicula) {
        const titulo = this.formPost.value.titulo as string;
        const generoNombre = this.formPost.value.genero as string;
        const genero = this.generoMapping[generoNombre]; 
        const anno_estreno = this.formPost.value.anno_estreno as number;
        const duracion_minutos = this.formPost.value.duracion_minutos as number;
        const imagen = this.formPost.value.imagen as string;

        const movie: peliculas = {
          id_pelicula,
          titulo,
          genero,
          anno_estreno,
          duracion_minutos,
          imagen
        };

        console.log(movie);

        this.apiService.actualizarDatoPut(movie) .subscribe(response => {
          console.log(response);
          this.obtenerDatos();
          this.setEditOpen(false, 0);
        });
      } else {
        console.error('El id_pelicula no es válido');
      }
    } 
  }
}