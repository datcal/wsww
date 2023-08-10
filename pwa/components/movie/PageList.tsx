import { NextComponentType, NextPageContext } from "next";
import { useRouter } from "next/router";
import Head from "next/head";
import { useQuery } from "react-query";

import Pagination from "../common/Pagination";
import { List } from "./List";
import { PagedCollection } from "../../types/collection";
import { Movie } from "../../types/Movie";
import { fetch, FetchResponse, parsePage } from "../../utils/dataAccess";
import { useMercure } from "../../utils/mercure";

export const getMoviesPath = (page?: string | string[] | undefined) =>
  `/movies${typeof page === "string" ? `?page=${page}` : ""}`;
export const getMovies = (page?: string | string[] | undefined) => async () =>
  await fetch<PagedCollection<Movie>>(getMoviesPath(page));
const getPagePath = (path: string) =>
  `/movies/page/${parsePage("movies", path)}`;

export const PageList: NextComponentType<NextPageContext> = () => {
  const {
    query: { page },
  } = useRouter();
  const { data: { data: movies, hubURL } = { hubURL: null } } = useQuery<
    FetchResponse<PagedCollection<Movie>> | undefined
  >(getMoviesPath(page), getMovies(page));
  const collection = useMercure(movies, hubURL);

  if (!collection || !collection["hydra:member"]) return null;

  return (
    <div>
      <div>
        <Head>
          <title>Movie List</title>
        </Head>
      </div>
      <List movies={collection["hydra:member"]} />
      <Pagination collection={collection} getPagePath={getPagePath} />
    </div>
  );
};
