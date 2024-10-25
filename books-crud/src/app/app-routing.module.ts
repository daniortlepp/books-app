import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { UserRegisterComponent } from './user-register/user-register.component';
import { LogoutComponent } from './logout/logout.component';
import { BooksListComponent } from './books-list/books-list.component';
import { BooksCreateComponent } from './books-create/books-create.component';
import { BooksEditComponent } from './books-edit/books-edit.component';
import { AuthGuard } from './auth.guard';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'register', component: UserRegisterComponent },
  { path: 'books', component: BooksListComponent, canActivate: [AuthGuard] },
  { path: 'books/create', component: BooksCreateComponent, canActivate: [AuthGuard] },
  { path: 'books/edit/:id', component: BooksEditComponent, canActivate: [AuthGuard] },
  { path: 'logout', component: LogoutComponent, canActivate: [AuthGuard] },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
