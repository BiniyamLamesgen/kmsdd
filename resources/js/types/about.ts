export interface About {
  id: number;
  content: string;
  image: string | null;  // ideally also allow null, since the resource returns null sometimes
}
