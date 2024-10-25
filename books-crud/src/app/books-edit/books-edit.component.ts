import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-books-edit',
  templateUrl: './books-edit.component.html',
  styleUrls: ['./books-edit.component.css']
})
export class BooksEditComponent implements OnInit {
  bookId!: number;
  title = '';
  description = '';
  author = '';
  numberOfPages = 0;

  constructor(private http: HttpClient, private route: ActivatedRoute, private router: Router) {}

  ngOnInit(): void {
    this.bookId = +this.route.snapshot.paramMap.get('id')!;
    this.http.get(`http://127.0.0.1:8000/api/books/${this.bookId}`).subscribe((book: any) => {
      this.title = book.title;
      this.description = book.description;
      this.author = book.author;
      this.numberOfPages = book.numberOfPages;
    }, error => {
      alert('Ocorreu um erro ao tentar carregar os dados do livro');
    });
  }

  updateBook() {
    const updatedBook = { title: this.title, author: this.author };
    this.http.put(`http://127.0.0.1:8000/api/books/${this.bookId}`, updatedBook).subscribe(() => {
      this.router.navigate(['/books']);
    }, error => {
      alert('Ocorreu um erro ao tentar salvar o livro');
    });
  }

  goBack() {
    this.router.navigate(['/books']);
  }
}
