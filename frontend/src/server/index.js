
import ShopServerConfig from "./shop-server";
import { products } from "@/constant/data";
import { faker } from "@faker-js/faker";
import { calendarEvents } from "./app/data";
import calendarServerConfig from "./app/calendar/calendar-server";
const previousDay = new Date(new Date().getTime() - 24 * 60 * 60 * 1000);
const dayBeforePreviousDay = new Date(
  new Date().getTime() - 24 * 60 * 60 * 1000 * 2
);
export function makeServer({ environment = "development" } = {}) {
  

 //return server;
}
