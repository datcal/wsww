import {
  GetStaticPaths,
  GetStaticProps,
  NextComponentType,
  NextPageContext,
} from "next";
import DefaultErrorPage from "next/error";
import Head from "next/head";
import { useRouter } from "next/router";
import { dehydrate, QueryClient, useQuery } from "react-query";

import { Form } from "../../../components/moviecategory/Form";
import { PagedCollection } from "../../../types/collection";
import { MovieCategory } from "../../../types/MovieCategory";
import { fetch, FetchResponse, getItemPaths } from "../../../utils/dataAccess";

const getMovieCategory = async (id: string | string[] | undefined) =>
  id
    ? await fetch<MovieCategory>(`/movie_categories/${id}`)
    : Promise.resolve(undefined);

const Page: NextComponentType<NextPageContext> = () => {
  const router = useRouter();
  const { id } = router.query;

  const { data: { data: moviecategory } = {} } = useQuery<
    FetchResponse<MovieCategory> | undefined
  >(["moviecategory", id], () => getMovieCategory(id));

  if (!moviecategory) {
    return <DefaultErrorPage statusCode={404} />;
  }

  return (
    <div>
      <div>
        <Head>
          <title>
            {moviecategory && `Edit MovieCategory ${moviecategory["@id"]}`}
          </title>
        </Head>
      </div>
      <Form moviecategory={moviecategory} />
    </div>
  );
};

export const getStaticProps: GetStaticProps = async ({
  params: { id } = {},
}) => {
  if (!id) throw new Error("id not in query param");
  const queryClient = new QueryClient();
  await queryClient.prefetchQuery(["moviecategory", id], () =>
    getMovieCategory(id)
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
  const paths = await getItemPaths(
    response,
    "movie_categories",
    "/moviecategorys/[id]/edit"
  );

  return {
    paths,
    fallback: true,
  };
};

export default Page;
