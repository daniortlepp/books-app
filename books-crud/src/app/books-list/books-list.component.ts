import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-books-list',
  templateUrl: './books-list.component.html',
  styleUrls: ['./books-list.component.css']
})
export class BooksListComponent implements OnInit {
  books: any[] = [];
  user = JSON.parse(localStorage.getItem('user') || '{}');

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.http.get('http://127.0.0.1:8000/api/books').subscribe((data: any) => {
      this.books = data;
    }, error => {
      alert('Ocorreu um erro ao tentar carregar os livros');
    });
  }

  deleteBook(id: number) {
    if (!confirm('Tem certeza que deseja excluir este livro?')) {
      return;
    }
    this.http.delete(`http://127.0.0.1:8000/api/books/${id}`).subscribe(() => {
      this.books = this.books.filter(book => book.id !== id);
    }, error => {
      alert('Ocorreu um erro ao tentar excluir o livro');
    });
  }

  logout() {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
}
