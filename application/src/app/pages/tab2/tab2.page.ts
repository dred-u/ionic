import { Component } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { UserDataService } from 'src/app/services/user-data.service';
import { favoritas } from  '../../interfaces'; // Importar la clase TopLevel desde index.ts
import { Router } from '@angular/router';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss']
})
export class Tab2Page {
  public res: favoritas[] = [];
  id: number;

  formPost = new FormGroup({
    titulo: new FormControl<string>('', [Validators.required]),
    genero: new FormControl<any>(null, [Validators.required]),
    anno_estreno: new FormControl<any>(null, [Validators.required]),
    duracion_minutos: new FormControl<any>(null, [Validators.required]),
    imagen: new FormControl<string>('', [Validators.required]),
  });

  constructor(private apiService: ApiService, private router: Router, private userDataService: UserDataService) {
    this.id = parseInt(this.userDataService.getId());
    this.res = []; 
  }

  ionViewWillEnter() {
    this.obtenerDatos();
  }

  obtenerDatos() {
    this.apiService.getFavoritas(this.id)
      .subscribe(resp => {
        if (Array.isArray(resp) && resp.length > 0) {
          this.res = resp.reverse();
        } else {
          this.res = []; // Asignar un array vacío cuando no hay elementos favoritos
        }
      }, error => {
        console.error("Error al obtener datos favoritos:", error);
        this.res = []; // Asignar un array vacío en caso de error
      });
  }

  eliminarDato(id: number) {
    const objetoEliminar = {
      id_favorita: id
    };
    this.apiService.eliminarFavorita(objetoEliminar).subscribe(
      (response: any) => {
          console.log(response);
          this.res = this.res.filter(item => item.id_favorita !== id);
      },
      error => {
        console.error("Error al eliminar el registro:", error);
      }
    );
  }

}
