import { Item } from "./item";

export class Movie implements Item {
  public "@id"?: string;

  constructor(_id?: string, public name?: string, public category?: string) {
    this["@id"] = _id;
  }
}
