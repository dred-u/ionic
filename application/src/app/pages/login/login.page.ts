import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ApiService } from 'src/app/services/api.service';
import { UserDataService } from 'src/app/services/user-data.service';
import { usuario } from 'src/app/interfaces';

@Component({
  selector: 'app-login',
  templateUrl: 'login.page.html',
  styleUrls: ['login.page.scss'],
})
export class LoginPage {

  formLog = new FormGroup({
    email: new FormControl<string>('',[Validators.required, Validators.email]),
    password: new FormControl<string>('',[Validators.required])
  })

  constructor(
   private apiService: ApiService,
   private userDataService: UserDataService,
   private router: Router
  ) { }

  async onSubmit() {
    if (this.formLog.valid) {
      const email = this.formLog.value.email as string;
      const password = this.formLog.value.password as string;
  
      const User: usuario = {
        email,
        password,
      };
      this.apiService.authenticate(User).subscribe(response => {
        console.log(response);

        const parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;
       
        const token = parsedResponse.token;
        const nombre = parsedResponse.usuario.nombre; 
        const id = parsedResponse.usuario.id_usuario; 
        this.userDataService.setUserData(id,email,nombre, token); 

        this.router.navigate(['/tabs']);
    });
    }
  }

}