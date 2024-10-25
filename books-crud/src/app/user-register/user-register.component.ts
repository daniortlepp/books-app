import { Component } from '@angular/core';
import { AuthService } from '../auth.service'; // Serviço de autenticação que você vai utilizar para fazer o cadastro
import { Router } from '@angular/router';

@Component({
  selector: 'app-user-register',
  templateUrl: './user-register.component.html',
  styleUrls: ['./user-register.component.css']
})
export class UserRegisterComponent {
  firstName = '';
  lastName = '';
  email = '';
  password = '';

  constructor(private authService: AuthService, private router: Router) {}

  register() {
    const userData = {
      firstName: this.firstName,
      lastName: this.lastName,
      email: this.email,
      password: this.password
    };

    this.authService.register(userData).subscribe(
      (response: any) => {
        if (response.status === 'success') {
          alert('Usuário registrado com sucesso!');
          this.router.navigate(['/login']); // Redireciona para a tela de login após o cadastro
        } else {
          alert(response.message);
        }
      },
      error => {
        alert('Erro ao registrar o usuário');
      }
    );
  }
}
