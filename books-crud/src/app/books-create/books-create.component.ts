import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-books-create',
  templateUrl: './books-create.component.html',
  styleUrls: ['./books-create.component.css']
})
export class BooksCreateComponent {
  title = '';
  description = '';
  author = '';
  numberOfPages = 0;

  constructor(private http: HttpClient, private router: Router) {}

  createBook() {
    const newBook = { title: this.title, description: this.description, author: this.author, numberOfPages: this.numberOfPages };
    this.http.post('http://127.0.0.1:8000/api/books', newBook).subscribe(() => {
      this.router.navigate(['/books']);
    }, error => {
      alert('Ocorreu um erro ao tentar criar o livro');
    });
  }

  goBack() {
    this.router.navigate(['/books']);
  }
}
