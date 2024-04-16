import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { usuario } from 'src/app/interfaces';
import { ApiService } from 'src/app/services/api.service';
import { UserDataService } from 'src/app/services/user-data.service';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage {

  formReg = new FormGroup({
    nombre: new FormControl<string>('',[Validators.required]),
    email: new FormControl<string>('',[Validators.required, Validators.email]),
    password: new FormControl<string>('',[Validators.required])
  })

  constructor(
    private apiService: ApiService,
    private userDataService: UserDataService,
    public navCntrl: NavController,
    private router: Router
  ) { }

  async onSubmit() {
    if (this.formReg.valid) {
      const nombre = this.formReg.value.nombre as string;
      const email = this.formReg.value.email as string;
      const password = this.formReg.value.password as string;
  
      const User: usuario = {
        nombre,
        email,
        password,
      };
      this.apiService.saveUserData(User).subscribe(response => {
        console.log("Usuario guardado en la base de datos:", response);

        const parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

        const token = parsedResponse.token;
        const nombre = parsedResponse.usuario.nombre; // Aquí obtenemos el nombre desde la respuesta
        const id = parsedResponse.usuario.id_usuario; 
        this.userDataService.setUserData(id,email,nombre, token); 

        this.router.navigate(['/tabs']);
      });
    }
  }
}
