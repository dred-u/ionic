import { Component } from '@angular/core';
import { UserDataService } from 'src/app/services/user-data.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-tab3',
  templateUrl: 'tab3.page.html',
  styleUrls: ['tab3.page.scss']
})
export class Tab3Page {
  email: string;
  nombre: string;

  constructor(private userDataService: UserDataService, private router: Router) {
    this.email = this.userDataService.getCorreo();
    this.nombre = this.userDataService.getNombre();
    console.log(this.email, this.nombre);
  }
  async signOut() {
    try {
      this.userDataService.clearUserData();
      this.router.navigate(['/login']);
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
      // Maneja el error según sea necesario
    }
  }


}
