import { Injectable, Pipe } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { peliculas, usuario } from '../interfaces';
import { Observable, pipe } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root',
})
export class ApiService {
  public apiUrl = 'http://127.0.0.1:80/movies-back/';

  constructor(private http: HttpClient) {}

  // Peliculas
  getTopHeadlines(): Observable<peliculas> {
    return this.http
      .get<peliculas>(`${this.apiUrl}movies`)
      .pipe(map((resp) => resp));
  }

  postDatos(datos: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}movies`, datos);
  }

  eliminarDato(id: any): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' })
    };
    return this.http.delete<any>(`${this.apiUrl}movies`, { ...httpOptions, body: id });
  }

  actualizarDatoPut(datos: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}movies`, datos);
  }

  actualizarDatoPatch(datos: any): Observable<any> {
    return this.http.patch<any>(`${this.apiUrl}movies`, datos);
  }

  //Usuarios
  saveUserData(user: usuario): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
      responseType: 'text' as 'json'
    };
    return this.http.post<any>(`${this.apiUrl}users`, user, {...httpOptions});
  }

  authenticate(user: usuario): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
      responseType: 'text' as 'json'
    };
    return this.http.post<any>(`${this.apiUrl}auth`, user, {...httpOptions});
  }

  //Favoritas
  getFavoritas(id: any): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
    };
    return this.http.get<any>(`${this.apiUrl}favorites?id_usuario=${id}`, httpOptions)
      .pipe(map(resp => resp));
  }

  postFavorita(datos: any): Observable<any> {
    console.log(datos)
    return this.http.post<any>(`${this.apiUrl}favorites`, datos);
  }

  eliminarFavorita(id: any): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' })
    };
    console.log(id)
    return this.http.delete<any>(`${this.apiUrl}favorites`, { ...httpOptions, body: id });
  }





}

