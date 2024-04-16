import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class UserDataService {
  constructor() { }

  setUserData(id: string, correo: string, nombre: string,  token: string) {
    sessionStorage.setItem('id', id);
    sessionStorage.setItem('correo', correo);
    sessionStorage.setItem('token', token);
    sessionStorage.setItem('nombre', nombre);
  }

  getCorreo(): string {
    return sessionStorage.getItem('correo') || '';
  }

  getId(): string {
    return sessionStorage.getItem('id') || '';
  }

  getNombre(): string {
    return sessionStorage.getItem('nombre') || '';
  }

  getToken(): string {
    return sessionStorage.getItem('token') || '';
  }

  clearUserData() {
    sessionStorage.removeItem('correo');
    sessionStorage.removeItem('token');
    sessionStorage.removeItem('nombre');
  }

}