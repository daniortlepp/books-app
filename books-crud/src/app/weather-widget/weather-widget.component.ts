import { Component, OnInit } from '@angular/core';
import { WeatherService } from '../weather.service';

@Component({
  selector: 'app-weather-widget',
  templateUrl: './weather-widget.component.html',
  styleUrls: ['./weather-widget.component.css']
})
export class WeatherWidgetComponent implements OnInit {
  weatherData: any;
  city = 'Porto Alegre';

  constructor(private weatherService: WeatherService) {}

  ngOnInit(): void {
    this.getWeather();
  }

  getWeather(): void {
    this.weatherService.getWeather(this.city).subscribe(
      (data: any) => {
        this.weatherData = data.results;
      },
      error => {
        console.error('Erro ao obter o clima', error);
      }
    );
  }

  changeCity(): void {
    this.getWeather();
  }
}
