import { GetStaticPaths, GetStaticProps } from "next";
import { dehydrate, QueryClient } from "react-query";

import {
  PageList,
  getMovieCategorys,
  getMovieCategorysPath,
} from "../../../components/moviecategory/PageList";
import { PagedCollection } from "../../../types/collection";
import { MovieCategory } from "../../../types/MovieCategory";
import { fetch, getCollectionPaths } from "../../../utils/dataAccess";

export const getStaticProps: GetStaticProps = async ({
  params: { page } = {},
}) => {
  const queryClient = new QueryClient();
  await queryClient.prefetchQuery(
    getMovieCategorysPath(page),
    getMovieCategorys(page)
  );

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
    },
    revalidate: 1,
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const response = await fetch<PagedCollection<MovieCategory>>(
    "/movie_categories"
  );
  const paths = await getCollectionPaths(
    response,
    "movie_categories",
    "/moviecategorys/page/[page]"
  );

  return {
    paths,
    fallback: true,
  };
};

export default PageList;
