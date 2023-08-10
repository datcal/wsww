import { NextComponentType, NextPageContext } from "next";
import { useRouter } from "next/router";
import Head from "next/head";
import { useQuery } from "react-query";

import Pagination from "../common/Pagination";
import { List } from "./List";
import { PagedCollection } from "../../types/collection";
import { MovieCategory } from "../../types/MovieCategory";
import { fetch, FetchResponse, parsePage } from "../../utils/dataAccess";
import { useMercure } from "../../utils/mercure";

export const getMovieCategorysPath = (page?: string | string[] | undefined) =>
  `/movie_categories${typeof page === "string" ? `?page=${page}` : ""}`;
export const getMovieCategorys =
  (page?: string | string[] | undefined) => async () =>
    await fetch<PagedCollection<MovieCategory>>(getMovieCategorysPath(page));
const getPagePath = (path: string) =>
  `/moviecategorys/page/${parsePage("movie_categories", path)}`;

export const PageList: NextComponentType<NextPageContext> = () => {
  const {
    query: { page },
  } = useRouter();
  const { data: { data: moviecategorys, hubURL } = { hubURL: null } } =
    useQuery<FetchResponse<PagedCollection<MovieCategory>> | undefined>(
      getMovieCategorysPath(page),
      getMovieCategorys(page)
    );
  const collection = useMercure(moviecategorys, hubURL);

  if (!collection || !collection["hydra:member"]) return null;

  return (
    <div>
      <div>
        <Head>
          <title>MovieCategory List</title>
        </Head>
      </div>
      <List moviecategorys={collection["hydra:member"]} />
      <Pagination collection={collection} getPagePath={getPagePath} />
    </div>
  );
};
